import { Component } from '@angular/core';
import { AppdataService } from 'src/app/service/appdata.service';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { MatDialog } from '@angular/material/dialog';
import { ProductService } from 'src/app/serice/product.service';
import { LocalService } from 'src/app/service/local.service';
@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent {
  uname: any;
  foods: any = '';
  counting: any;
  isshow: any;
  constructor(
    private dataService: AppdataService,
    private http: HttpClient,
    private dialog: MatDialog,
    private router: Router,
    private local: LocalService) {

    // console.log(this.count);
    this.isshow = dataService.isShowCart;
    // console.log(this.isshow);

    this.http.get(this.dataService.apiEndpoint + '/cartcount/' + this.local.getData("USER")).subscribe((data: any) => {
      console.log(data);
      console.log(data[0]);
      let total = data[0].count
      console.log("Lengh:" + total);
      this.counting = total
    });
  }
  cartLink() {
    console.log("cartLint");
    this.router.navigateByUrl("/cart");
  }
  listOrder() {
    console.log("listorder");
    this.router.navigateByUrl("/listcustomer");
  }
  logout() {
    console.log("logout");
    this.router.navigateByUrl("/login");
  }
}


