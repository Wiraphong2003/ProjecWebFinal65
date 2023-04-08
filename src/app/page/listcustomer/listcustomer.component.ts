import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AppdataService } from 'src/app/service/appdata.service';
import { LocalService } from 'src/app/service/local.service';

@Component({
  selector: 'app-listcustomer',
  templateUrl: './listcustomer.component.html',
  styleUrls: ['./listcustomer.component.scss']
})
export class ListcustomerComponent {

  panelOpenState = false;
  color!:any;
  listOrder: Array<any> = [];
  listcart_id !: any;
  listcartArray !: any;
  // listOrder!:any;
  arrayFood!: any;
  Food!: any;
  isshowlist = true;
  constructor(private router: Router,
    private http: HttpClient,
    private dataService: AppdataService,
    private local: LocalService) {

    this.http.get(this.dataService.apiEndpoint + '/ioders/' + local.getData("USER")).subscribe((data: any) => {
      this.listOrder = data;
      console.log(this.listOrder);
      data.forEach((element: any) => {
        // if(element.cusid)
        let cid: String = element.cartSTR
        console.log("cartSTR: " + cid);
        this.listcart_id = cid;
        // this.listcartArray = cid.split(",");
        // console.log(this.listcartArray);
        console.log(data.length === 0);
        if (data.length === 0) {
          this.isshowlist = true;
        } else {
          this.isshowlist = false;
        }
      });
    });



    this.http.get(this.dataService.apiEndpoint + '/getlistFoodorders').subscribe((data: any) => {
      this.Food = data;
      console.log(data);

      // console.log(this.Food);
    });

  }
  backmain() {
    this.router.navigateByUrl("main");
  }
  isEmpty(obj: Record<string, any>): boolean {
    for (const _key in obj) {
      return false;
    }
    return true;
  }
}
