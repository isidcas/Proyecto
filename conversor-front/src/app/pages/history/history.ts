import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { Navbar } from '../../components/navbar/navbar';

@Component({
  selector: 'app-history',
  standalone: true,
  imports: [CommonModule, HttpClientModule, Navbar],
  templateUrl: './history.html',
  styleUrls: ['./history.css']
})
export class HistoryPage implements OnInit {
  historial: any[] = [];
  emailUsuario: string = '';

  constructor(private http: HttpClient) {}

  ngOnInit() {
  const userSession = localStorage.getItem('usuario');
  if (userSession) {
    const user = JSON.parse(userSession);
    const usuarioId = user.id ? user.id : 1; // Recuperamos su ID numérico

    this.http.get(`http://localhost/Proyect/public/get-history.php?usuario_id=${usuarioId}`)
      .subscribe({
        next: (res: any) => this.historial = res.error ? [] : res,
        error: (err) => console.error(err)
      });
  }
}
}