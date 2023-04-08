import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { AppdataService } from 'src/app/service/appdata.service';
import { LocalService } from 'src/app/service/local.service';
import { PaymenyComponent } from '../paymeny/paymeny.component';

@Component({
  selector: 'app-detail-order',
  templateUrl: './detail-order.component.html',
  styleUrls: ['./detail-order.component.scss']
})
export class DetailOrderComponent {
  foodcart!: any[];
  amountALL !: any;
  vatamount !: any;
  totaldef!: any;

  Fname !: any;
  Lname !: any;
  address !: any;
  phone !: any;
  detail !: any;
  cart_ids: any;
  cartSTR: string = "";
  time: string = ""

  arraycartAmounts !: any;

  constructor(private dataService: AppdataService,
    private dialogRef: MatDialogRef<DetailOrderComponent>,
    private route: Router,
    private http: HttpClient,
    private dialog: MatDialog,
    private localS: LocalService,
  ) {
    this.totaldef = dataService.total;
    this.http.get(this.dataService.apiEndpoint + '/cart/' + localS.getData("USER")).subscribe((data: any) => {
      console.log(data);
      this.foodcart = data
    });

    this.http.get(this.dataService.apiEndpoint + '/cartsumprice/' + localS.getData("USER")).subscribe((data: any) => {
      console.log(data);
      console.log(data[0]);
      let total = data[0].total
      console.log("TOTAL:" + total);
      this.amountALL = total
    });

    this.http.get(this.dataService.apiEndpoint + '/getcart_id/' + this.localS.getData("USER")).subscribe((data: any) => {
      this.cart_ids = data;

    });
    
    this.vatamount = (0.07 * this.dataService.total) + this.dataService.total
    console.log((0.07 * this.dataService.total) + this.dataService.total);
  }
  close() {
    this.dialogRef.close();
  }

  confirm() {
    const now = new Date();
    this.cartSTR = ""
    this.cart_ids.forEach((element: any) => {
      let cid: String = element.cart_id
      this.cartSTR += cid + ',';
      // console.log(this.cartSTR);
    });

    // console.log("Ans " + this.cartSTR);
    // console.log((this.cartSTR.trim()).split(','));
    const editedText = this.cartSTR.slice(0, -1)
    this.time = now.toLocaleString();


    let text;
    let Json
    console.log("Fanem: " + this.Fname);
    console.log("phone: " + this.phone);
    console.log("address: " + this.address);

    if (this.Fname === undefined &&
      this.phone === undefined &&
      this.address == undefined) {
      if (confirm("กรุณากรอกข้อมูลให้ครบถ้วน") == true) {
        text = "You pressed OK!";
      } else {
        text = "!";
      }
    } else {
      Json = {
        cname: this.Fname + " " + this.Lname,
        cphone: this.phone,
        address: this.address,
        detail: this.detail,
        totalPrice: this.vatamount,
        time: this.time,
        status: "ยังไม่ส่ง",
        cusid: this.localS.getData("USER"),
        cartSTR: editedText
      }
      console.log(Json);

      this.dialogRef.close();

      this.dataService.orderdetalt = Json;
      this.dialog.open(PaymenyComponent, {
        minWidth: '300px'
      });


    }
  }
}
