import { HttpClient } from '@angular/common/http';
import { Component, OnInit, VERSION } from '@angular/core';
import { MatDialogRef } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { Food } from 'src/app/model/food.model';
import { ProductService } from 'src/app/serice/product.service';
import { AppdataService } from 'src/app/service/appdata.service';
import { LocalService } from 'src/app/service/local.service';

export interface Tile {
  color: string;
  cols: number;
  rows: number;
  text: string;
}
@Component({
  selector: 'app-amount',
  templateUrl: './amount.component.html',
  styleUrls: ['./amount.component.scss']
})
export class AmountComponent {


  foodOj: Food;
  name = `Angular ${VERSION.major}`;
  amount = 1;
  sumprice = 0;
  session: any;

  productList!: any[];
  products: any[] = [];
  subTotal!: any;
  foodcart!: any[];
  tempfood = ""
  tempamount: any;
  constructor(private dataService: AppdataService,
    private dialogRef: MatDialogRef<AmountComponent>,
    private http: HttpClient,
    private local: ProductService,
    private localUser: LocalService,
    private router: Router) {
    this.foodOj = dataService.FoodServic;

    this.local.loadCart();
    this.products = this.local.getProduct();
    console.log("Foodamounts: " + this.foodOj.fid);

    this.http.get(this.dataService.apiEndpoint + '/cart/' + localUser.getData("USER")).subscribe((data: any) => {
      console.log(data);
      this.foodcart = data

      data.forEach((ss: any) => {
        if (this.foodOj.fid === ss.fid) {
          this.tempamount = ss.amount;
        }
      });
    });




  }

  close() {
    this.dialogRef.close();
  }

  addToCart(fid: any) {
    if (this.amount >= 1) {
      this.http.get(this.dataService.apiEndpoint + '/cart/' + this.localUser.getData("USER")).subscribe((data: any) => {
        console.log(data);
        this.foodcart = data
      });
      const result = this.foodcart.some((obj) => {
        if (obj.fid === fid) {
          // this.tempfood.push(obj.fid)
          console.log("FOODFID: " + obj.fid);
          console.log("FOODAMOUNT: " + obj.amount);
          this.tempamount = obj.amount;

          this.tempfood = fid

        }
        return obj.fid === fid;
      });

      console.log(result); // 👉️ true

      let insert = {
        uid: this.localUser.getData("USER"),
        food_id: fid,
        amount: this.amount
      }

      if (!result) {
        this.http.post(this.dataService.apiEndpoint + '/insertcart',
          (JSON.stringify(insert))).subscribe((cart: any) => {
            console.log(cart);
          });
      }
      else{
        let text;
        console.log("insert2:" + insert);
        if (confirm("มีสินค้าในตะกร้าแล้ว ต้องการ Update เพิ่มหรือไม่") == true) {
          text = "You pressed OK!";
          let inserts = {
            uid: this.localUser.getData("USER"),
            food_id: fid,
            amount: this.amount
          }
          this.http.post(this.dataService.apiEndpoint + '/updatecart',
            (JSON.stringify(inserts))).subscribe((cart: any) => {
              console.log(cart);
            });
        }
        else {
          text = "You canceled!";
        }
      }
    }else{
      let text;
      if(confirm("ต้องเลือกจำนวนสินค้ามากกว่า 0 เท่านั้น")==true){
        text = "You pressed OK!";
      }
      else{
        text = "You canceled!";
      }
    }
    this.dialogRef.close();
  }
}
