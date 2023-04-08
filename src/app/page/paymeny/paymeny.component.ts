import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { MatDialogRef } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { AppdataService } from 'src/app/service/appdata.service';
import { LocalService } from 'src/app/service/local.service';

@Component({
  selector: 'app-paymeny',
  templateUrl: './paymeny.component.html',
  styleUrls: ['./paymeny.component.scss']
})
export class PaymenyComponent {


  Json !: any;
  arraycartAmounts !: any;

  constructor(private dialogRef: MatDialogRef<PaymenyComponent>,
    private http: HttpClient,
    private localS: LocalService,
    private dataService: AppdataService,
    private router: Router
  ) {
    this.Json = dataService.orderdetalt;

  }
  close() {
    this.dialogRef.close();
  }
  OK() {
    console.log("OK");

    console.log(this.Json);
    this.http.post(this.dataService.apiEndpoint + '/insertIorder', (JSON.stringify(this.Json))).subscribe((e: any) => {
      console.log(e);
      let oided = e.oid;
      console.log("oided: " + oided);

      let jjj = {
        "oid": oided,
        "uid": this.localS.getData("USER")
      }

      this.http.post(this.dataService.apiEndpoint + '/updatecartOID', (JSON.stringify(jjj))).subscribe((e: any) => {

        this.http.get(this.dataService.apiEndpoint + '/getcartOid/' + oided).subscribe((data: any) => {
          this.arraycartAmounts = data;

          console.log("arraycartAmounts" + data);
          this.arraycartAmounts.forEach((data: any) => {
            let ss = {
              cart_id: data.cart_id,
              uid: data.uid,
              food_id: data.food_id,
              amount: data.amount,
              oid: data.oid
            }
            console.log(ss);

            this.http.post(this.dataService.apiEndpoint + '/pushcartAmount',
              (JSON.stringify(ss))).subscribe((e: any) => {
                console.log(e);
                let ssG = {
                  uid: this.localS.getData("USER")
                }
                this.http.post(this.dataService.apiEndpoint + '/deletecartAll',
                  (JSON.stringify(ssG))).subscribe((e: any) => {
                    console.log(e);
                  });
              });

          });

        });

      });

    });


    this.dialogRef.close();
    // let ss = {
    //   uid: this.localS.getData("USER")
    // }
    // this.http.post(this.dataService.apiEndpoint + '/deletecartAll',
    //   (JSON.stringify(ss))).subscribe((e: any) => {
    //     console.log(e);
    //   });
  }
}
