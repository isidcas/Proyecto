import { Routes } from '@angular/router';
import { Login } from './pages/login/login';
import { Register } from './pages/register/register';
import { Dashboard } from './pages/dashboard/dashboard';
import { Historial } from './pages/historial/historial';
import { Contacto } from './pages/contacto/contacto';

export const routes: Routes = [
{path:'', component: Login },
  { path:'registro', component: Register },
  { path:'dashboard', component: Dashboard },
  { path:'historial', component: Historial },
  { path:'contacto', component: Contacto }

];
