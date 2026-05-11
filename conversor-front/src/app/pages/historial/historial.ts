import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Navbar } from "../../components/navbar/navbar";

@Component({
  selector: 'app-historial',
  standalone: true,
  imports: [CommonModule, Navbar],
  templateUrl: './historial.html',
  styleUrl: './historial.css'
})
export class Historial {

  data = [
    { from: 'USD', to: 'EUR', amount: 100, result: 92 }
  ];
}