import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormGroup, FormControl, Validators, FormsModule } from '@angular/forms';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { Router, RouterLink } from '@angular/router';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, FormsModule, HttpClientModule, RouterLink],
  templateUrl: './register.html',
  styleUrls: ['./register.css']
})
export class RegisterPage {
  // Creamos el formulario reactivo
  registerForm = new FormGroup({
    nombre: new FormControl('', [Validators.required]),
    email: new FormControl('', [Validators.required, Validators.email]),
    password: new FormControl('', [Validators.required, Validators.minLength(6)])
  });

  constructor(private http: HttpClient, private router: Router) {}

  onRegister() {
  if (this.registerForm.valid) {
    this.http.post('http://localhost/Proyect/public/register.php', this.registerForm.value)
      .subscribe({
        next: (res: any) => {
          // IMPORTANTE: El registro devuelve "success", no "user"
          if (res.success) {
            alert("¡Registro exitoso! Ahora puedes iniciar sesión.");
            this.router.navigate(['/login']);
          } else {
            alert(res.error); // Aquí te dirá si el email ya existe, etc.
          }
        },
        error: (err) => alert("Error de conexión con el servidor")
      });
  }
}
}