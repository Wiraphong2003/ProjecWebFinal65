import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { AppdataService } from 'src/app/service/appdata.service';
import { MatDialog } from '@angular/material/dialog';
import { InsertmenuComponent } from '../../page/admin/insertmenu/insertmenu.component';
import { EditComponent } from "../../page/admin/edit/edit.component";





@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.scss']
})
export class AdminComponent {

  foods: any = '';
  type: any = '';
  constructor(
    private dataService: AppdataService,
    private http: HttpClient,
    private dialog: MatDialog
  ) {
    http.get(dataService.apiEndpoint + '/foods').subscribe((data: any) => {
      // console.log(data);
      this.foods = data;
    });
    http.get(dataService.apiEndpoint + '/types').subscribe((types: any) => {
      this.type = types;
    });
  }
  installProduct(){
    console.log("Install Product");
    this.dialog.open(InsertmenuComponent,{
      // width : '550px'
      minHeight: '300px',
      minWidth: '400px'
    })
  }
  Open(data:any){
    console.log(data);
    this.dataService.FoodServic = data;
    this.dialog.open(EditComponent,{
      width : '550px'
    })
  }

  allMenu() {
    this.http.get(this.dataService.apiEndpoint + '/foods').subscribe((data: any) => {
      console.log(data);
      this.foods = data;
    });
  }
  getMenu(type: string) {
    this.http.post(this.dataService.apiEndpoint + '/typees', (JSON.stringify({ "type": type }))).subscribe((types: any) => {
      this.foods = types;
    });
  }

  delete(fid : any){
    this.http.post(this.dataService.apiEndpoint+'/deletemenu',(JSON.stringify({"fid" : fid}))).subscribe();
    console.log(fid);
  }
  tmp(){
    console.log("tock");
  }
}
