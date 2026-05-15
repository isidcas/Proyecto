import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({ providedIn: 'root' })
export class Auth {
  url = 'http://localhost/api'; // Ajusta según tu ruta

  constructor(private http: HttpClient) {}

  register(user: any) { return this.http.post(`${this.url}/register.php`, user); }
  login(user: any) { return this.http.post(`${this.url}/login.php`, user); }
  convertir(datos: any) { return this.http.post(`${this.url}/convert.php`, datos); }
}