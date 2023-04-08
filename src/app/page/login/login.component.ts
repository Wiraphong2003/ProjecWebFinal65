import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AppdataService } from 'src/app/service/appdata.service';
import { LocalService } from 'src/app/service/local.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  username!: string;
  password!: string;
  dataList: any;
  userLocal:any;
  constructor(private http: HttpClient,
    private router: Router,
    private dateService: AppdataService,
    private Local:LocalService
  ) {
  }
  login() {
    console.log("Login");
    let Json = { username: this.username, password: this.password };
    this.http.post('http://localhost:9999/webAPI_ProjectWebFinal/login',
      JSON.stringify(Json)).subscribe((response: any) => {
        console.log(response);
        this.dataList = response;
        // this.dataList.username
        if (this.dataList.status === "owner") {
          this.dateService.isShowCart = 1;
          this.router.navigateByUrl("admin");

        } else if (this.dataList.status === "customer") {
          this.dateService.isShowCart = 0
          this.router.navigateByUrl("main");
          this.dateService.userNow = this.dataList.uid
          this.Local.saveData('USER',this.dataList.uid)
        }
        else {
          console.log("else check");

        }
      }, Error => {
        console.log("Fail");
      });
  }
}
