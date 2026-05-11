import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})

export class Auth {

  api ='http://localhost/Proyect/public/';
  

  constructor(private http: HttpClient) {}

  register(data:any){

    return this.http.post(
      this.api + 'register.php',
      data
    );
  }
    login(data:any){

    return this.http.post(
      this.api + 'login.php',
      data
    );
  }
}

