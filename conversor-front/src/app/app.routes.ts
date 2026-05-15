import { Routes } from '@angular/router';
import { Dashboard } from './pages/dashboard/dashboard';
import { HistoryPage } from './pages/history/history'; // Crea esta clase
import { Login } from './pages/login/login';
import { RegisterPage } from './pages/register/register';


export const routes: Routes = [
  { path: 'dashboard', component: Dashboard },
  { path: 'historial', component: HistoryPage },
  { path: 'login', component: Login },
  { path: 'register', component: RegisterPage},
  { path: '', redirectTo: '/login', pathMatch: 'full' }
];