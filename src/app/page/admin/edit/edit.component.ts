import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { Food } from 'src/app/model/food.model';
import { AppdataService } from 'src/app/service/appdata.service';

@Component({
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.scss']
})
export class EditComponent {
  foods:any;
  FoodOj!: Food
  constructor(private dataService:AppdataService,
    private http:HttpClient,
    ){
    this.foods = dataService.FoodServic;
    console.log(this.foods);

  }
}
