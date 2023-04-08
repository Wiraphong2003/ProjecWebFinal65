import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { AppdataService } from '../service/appdata.service';
@Injectable({
  providedIn: 'root',
})
export class ProductService {
  products: any[] = [];
  count: any;

  constructor(private http: HttpClient,
    private dataSerce: AppdataService) {
    this.setCount();
  }
  getProduct() {
    return this.products;
  }
  saveCart(): void {
    this.setCount();
    localStorage.setItem('cart_items', JSON.stringify(this.products));
  }

  addToCart(addedProduct: any) {
    this.setCount();
    this.products.push(addedProduct);
    this.saveCart();
  }

  loadCart(): void {
    this.setCount();
    this.products = JSON.parse(localStorage.getItem('cart_items') as any) || [];
  }

  productInCart(product: any): boolean {
    this.setCount();
    return this.products.findIndex((x: any) => x.id === product.fid) > -1;
  }

  removeProduct(product: any) {
    this.setCount();
    const index = this.products.findIndex((x: any) => x.id === product.id);

    if (index > -1) {
      this.products.splice(index, 1);
      this.saveCart();
    }
  }
  setCount() {
    this.count = this.products.length;
  }
  getCount() {
    return this.count;
  }

}
