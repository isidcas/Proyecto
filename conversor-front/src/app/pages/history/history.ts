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
  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.http.get('http://localhost/Proyect/public/get-history.php?usuario_id=1')
      .subscribe((res: any) => this.historial = res);
  }
}