import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import Chart from 'chart.js/auto';
import { Navbar } from "../../components/navbar/navbar";
import { ConversionService } from '../../services/conversion';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule, Navbar],
  templateUrl: './dashboard.html',
  styleUrls: ['./dashboard.css']
})
export class Dashboard implements OnInit {
  conversion = { cantidad: 1, origen: 'EUR', destino: 'USD' };
  resultado: number | null = null;
  tasaCambio: number | null = null;
  divisasSoportadas: string[] = ['EUR', 'USD', 'GBP', 'JPY', 'MXN']; 

  // 1. VALORES ESTÁTICOS: Para que la interfaz nunca esté vacía
  marketRates: any[] = [
    { codigo: 'USD', valor: 1.08, data: [1.07, 1.09, 1.08, 1.08] },
    { codigo: 'GBP', valor: 0.85, data: [0.84, 0.86, 0.85, 0.85] },
    { codigo: 'JPY', valor: 162.5, data: [161, 163, 162, 162.5] },
    { codigo: 'MXN', valor: 18.2, data: [18.0, 18.4, 18.1, 18.2] }
  ];

  private charts: any[] = [];

  constructor(private conversionService: ConversionService) {}

  ngOnInit() {
    this.cargarDivisas();
    // Renderizado inicial con datos estáticos
    setTimeout(() => this.renderCharts(), 500);
  }

  cargarDivisas() {
    this.conversionService.getCurrencies().subscribe({
      next: (res) => {
        if (res) {
          this.divisasSoportadas = Object.keys(res);
          this.actualizarMercado();
        }
      },
      error: () => console.warn("Usando lista de divisas estática")
    });
  }
convertir() {
    if (this.conversion.cantidad <= 0) return;

    // 1. Recuperamos el ID numérico del usuario logueado
    const userSession = localStorage.getItem('usuario');
    let usuarioId = 1; // Valor por defecto numérico si no hay sesión para pruebas

    if (userSession) {
      const user = JSON.parse(userSession);
      if (user && user.id) {
        usuarioId = Number(user.id); // Nos aseguramos de que sea un número entero
      }
    }

    console.log("Enviando POST a convert.php con ID de usuario:", usuarioId);

    // 2. Enviamos el objeto con el ID numérico
    this.conversionService.convertir({
      amount: this.conversion.cantidad,
      from: this.conversion.origen,
      to: this.conversion.destino,
      usuario_id: usuarioId // <-- Cambiamos 'email' por 'usuario_id' numérico
    }).subscribe({
      next: (res: any) => {
        if (res?.rates && res.rates[this.conversion.destino]) {
          this.resultado = res.rates[this.conversion.destino];
          this.tasaCambio = this.resultado! / this.conversion.cantidad;
        }
      },
      error: (err) => console.error("Error en la conversión:", err)
    });
  }
  
  actualizarMercado() {
    this.conversionService.getMarketRates().subscribe({
      next: (res: any) => {
        const populares = ['USD', 'GBP', 'JPY', 'MXN'];
        
        // CORRECCIÓN CRÍTICA: Validamos que res.rates exista antes de mapear
        if (res?.rates) {
          this.marketRates = populares.map(code => {
            // Si la moneda no existe en la respuesta, usamos el valor estático previo
            const valorApi = res.rates[code];
            const valorFinal = valorApi ? valorApi : (this.marketRates.find(m => m.codigo === code)?.valor || 0);

            return {
              codigo: code,
              valor: valorFinal,
              data: [valorFinal * 0.99, valorFinal * 1.01, valorFinal * 0.98, valorFinal]
            };
          });
          setTimeout(() => this.renderCharts(), 100);
        }
      },
      error: () => console.log("Manteniendo datos de mercado estáticos")
    });
  }

  renderCharts() {
    // Destruir instancias previas para evitar el error "Canvas is already in use"
    this.charts.forEach(c => c.destroy());
    this.charts = [];

    this.marketRates.forEach(r => {
      const el = document.getElementById(`chart-${r.codigo}`) as HTMLCanvasElement;
      if (el) {
        const chart = new Chart(el, {
          type: 'line',
          data: {
            labels: ['', '', '', ''],
            datasets: [{ 
              data: r.data, 
              borderColor: '#6200ee', 
              borderWidth: 2, 
              pointRadius: 0, 
              fill: false, 
              tension: 0.4 
            }]
          },
          options: { 
            plugins: { legend: { display: false } }, 
            scales: { x: { display: false }, y: { display: false } },
            responsive: true, 
            maintainAspectRatio: false 
          }
        });
        this.charts.push(chart);
      }
    });
  }
}