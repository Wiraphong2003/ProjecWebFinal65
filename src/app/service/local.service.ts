import { Injectable } from '@angular/core';
import { Food } from '../model/food.model';
import { AppdataService } from './appdata.service';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class LocalService {


  items = [""];
  datakey: any;
  constructor(private dataService: AppdataService,
  ) {

  }
  public saveData(key: string, value: string) {
    localStorage.setItem(key, value);
    this.items.push(value)
  }
  public getData(key: string) {
    return localStorage.getItem(key)
  }
  public removeData(key: string) {
    localStorage.removeItem(key);
  }
  public getlistsecssion() {
    return this.items;
  }

  public clearData() {
    localStorage.clear();
  }
}
