import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AppdataService } from 'src/app/service/appdata.service';
import { LocalService } from 'src/app/service/local.service';

@Component({
  selector: 'app-head-admin',
  templateUrl: './head-admin.component.html',
  styleUrls: ['./head-admin.component.scss']
})
export class HeadAdminComponent {
  constructor(
    private dataService: AppdataService,
    private http: HttpClient,
    private local: LocalService,
    private router: Router,
  ) {

  }
  listOrder() {
    console.log("listorder");
    this.router.navigateByUrl("/orderadmin");
  }
  logout() {
    console.log("logout");
    this.router.navigateByUrl("/login");
  }
}
