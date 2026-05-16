import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class ConversionService {
  // Asegúrate de que esta URL sea la correcta de tu contenedor PHP
  private url = 'http://localhost/Proyect/public/convert.php';

  constructor(private http: HttpClient) {}

  // Obtener lista de monedas (usando un parámetro de acción)
  getCurrencies(): Observable<any> {
    return this.http.get(`${this.url}?action=currencies`);
  }

  // Realizar la conversión
  convertir(datos: any): Observable<any> {
    return this.http.post(this.url, datos);
  }

  // Obtener tasas de mercado para las gráficas
  getMarketRates(): Observable<any> {
    return this.http.get(`${this.url}?action=market`);
  }

  getHistorial(email: string): Observable<any> {
  // Apuntamos directamente a tu archivo existente
  return this.http.get(`http://localhost/Proyect/public/get-history.php?email=${email}`);
}
}