import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { AppdataService } from 'src/app/service/appdata.service';
import { MatDialog } from '@angular/material/dialog';
import { AmountComponent } from '../amount/amount.component';
import { LocalService } from 'src/app/service/local.service';
import { HeaderComponent } from '../header/header.component';
@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.scss']
})
export class MainComponent {
  foods: any = '';
  type: any = '';
  user:any;
  constructor(
    private dataService: AppdataService,
    private http: HttpClient,
    private dialog: MatDialog,
    private local:LocalService
  ) {
    http.get(dataService.apiEndpoint + '/foods').subscribe((data: any) => {
      console.log(data);
      this.foods = data;
    });
    http.get(dataService.apiEndpoint + '/types').subscribe((types: any) => {
      this.type = types;
    });

    this.user  =   dataService.userNow
    console.log(this.user);

  }

  allMenu() {
    this.http.get(this.dataService.apiEndpoint + '/foods').subscribe((data: any) => {
      console.log(data);
      this.foods = data;
    });
  }
  getMenu(type: string) {
    this.http.post(this.dataService.apiEndpoint + '/typees',
    (JSON.stringify({ "type": type }))).subscribe((types: any) => {
      this.foods = types;
    });
  }
  amount(foods: string) {
    console.log("amount");
    console.log(foods);
    this.dataService.FoodServic = foods;
    this.dialog.open(AmountComponent, {
      minWidth: '300px'
    })
  }
}
