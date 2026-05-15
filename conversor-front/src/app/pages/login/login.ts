import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { Router, RouterLink } from '@angular/router';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, HttpClientModule, RouterLink],
  templateUrl: './login.html',
  styleUrls: ['./login.css']
})
export class Login {
  loginForm = new FormGroup({
    email: new FormControl('', [Validators.required, Validators.email]),
    password: new FormControl('', [Validators.required, Validators.minLength(4)])
  });

  constructor(private http: HttpClient, private router: Router) {}

  onLogin() {
    if (this.loginForm.valid) {
      this.http.post('http://localhost/Proyect/public/login.php', this.loginForm.value)
        .subscribe({
          next: (res: any) => {
            if (res.user) {
              localStorage.setItem('usuario', JSON.stringify(res.user));
              this.router.navigate(['/dashboard']);
            } else {
              alert("Email o contraseña incorrectos");
            }
          },
          error: () => alert("Error de conexión con el servidor PHP")
        });
    }
  }
}