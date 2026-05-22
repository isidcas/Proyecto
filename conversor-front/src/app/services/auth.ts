import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({ providedIn: 'root' })
export class Auth {
  // 🔽 CAMBIAMOS ESTA LÍNEA: Pon la URL de tu backend en Render
  // (Si en Render configuraste el Publish Directory como 'public', quita el '/public' de la URL)
  url = 'https://conversor-divisas-backend.onrender.com/public'; 

  constructor(private http: HttpClient) {}

  register(user: any) { return this.http.post(`${this.url}/register.php`, user); }
  login(user: any) { return this.http.post(`${this.url}/login.php`, user); }
  convertir(datos: any) { return this.http.post(`${this.url}/convert.php`, datos); }
}