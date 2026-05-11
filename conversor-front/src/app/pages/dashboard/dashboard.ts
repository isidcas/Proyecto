import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Conversion } from '../../services/conversion';
import { Navbar } from '../../components/navbar/navbar';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, FormsModule, Navbar],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css'
})
export class Dashboard {

  conversion = {
    origen: 'USD',
    destino: 'EUR',
    cantidad: 0
  };

  resultado: any = null;

  constructor(private service: Conversion) {}

  convertir() {
    this.service.convertir(this.conversion)
      .subscribe((res: any) => {
        this.resultado = res.resultado;
      });
  }
}