import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root',
})
export class Auth {
  registro(datos:any){
  return this.http.post(
    'http://localhost/Proyect/public/register.php',
    datos
  );
}

}
