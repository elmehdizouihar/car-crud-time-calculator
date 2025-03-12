import { Routes } from '@angular/router';
import { CarListComponent } from './modules/car/components/car-list/car-list.component';
import { CarFormComponent } from './modules/car/components/car-form/car-form.component';
import { TimeCalculationComponent } from './modules/car/components/time-calculation/time-calculation.component';


export const routes: Routes = [
    { path: '', component: CarListComponent },
    { path: 'add-car', component: CarFormComponent },
    { path: 'edit-car/:id', component: CarFormComponent },
    { path: 'time-calculation', component: TimeCalculationComponent },

];
