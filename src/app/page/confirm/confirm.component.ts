import { Component } from '@angular/core';
import { AppdataService } from 'src/app/service/appdata.service';
import { LocalService } from 'src/app/service/local.service';
import { ShoppingCartComponent } from '../shopping-cart/shopping-cart.component';
import { HttpClient } from '@angular/common/http';
import { MatDialogClose } from '@angular/material/dialog';
import { Router } from '@angular/router';

@Component({
  selector: 'app-confirm',
  templateUrl: './confirm.component.html',
  styleUrls: ['./confirm.component.scss']
})
export class ConfirmComponent {
  title: any
  message: any
  btnCancelText: any
  btnOkText: any
  fid: any;
  foodcart!: any[];
  constructor(private localS: LocalService,
    private dataService: AppdataService,
    private http: HttpClient,
    private router: Router,) {
    this.fid = dataService.FoodServic
  }
  dismiss() {

  }
  decline() {

  }
  getTotal() {
    this.http.get(this.dataService.apiEndpoint + '/cartsumprice/' + this.localS.getData("USER")).subscribe((data: any) => {
      console.log(data);
      console.log(data[0]);
      let total = data[0].total
      console.log("TOTAL:" + total);
    });
  }
  accept() {
    console.log(this.fid);
    let Jsonamount = {
      uid: this.localS.getData("USER"),
      food_id: this.fid,
    }
    this.http.post(this.dataService.apiEndpoint + '/deletecart',
      (JSON.stringify(Jsonamount))).subscribe((e: any) => {
        console.log(e);

        this.http.get(this.dataService.apiEndpoint + '/cart/' + this.localS.getData("USER")).subscribe((data: any) => {
          console.log(data);
          this.foodcart = data
          this.getTotal()
        });
      });
  }
}
