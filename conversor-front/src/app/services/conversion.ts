import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class Conversion {

  api = 'http://localhost/Proyect/public/convert.php';

  constructor(private http: HttpClient){}

  convertir(data:any){

    return this.http.post(
      this.api,
      data
    );

  }

}