import { Component, OnInit } from '@angular/core';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatCardModule } from '@angular/material/card';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { MatChipsModule } from '@angular/material/chips';
import { ToastrService } from 'ngx-toastr';
import { CarService } from '../../services/car.service';
import { LoadingSpinnerComponent } from '../../../../core/shared/components/loading-spinner/loading-spinner.component';

@Component({
  selector: 'app-time-calculation',
  imports: [
    MatInputModule,
    MatButtonModule,
    MatFormFieldModule,
    MatIconModule,
    MatCardModule,
    FormsModule,
    CommonModule,
    MatChipsModule,
    LoadingSpinnerComponent
  ],
  standalone: true,
  templateUrl: './time-calculation.component.html',
  styleUrl: './time-calculation.component.css'
})
export class TimeCalculationComponent implements OnInit {
  formData: { model: string, distance: null } = { model: '', distance: null };
  errors: { model: string, distance: string } = { model: '', distance: '' };
  calculatedTime: boolean = false;
  calculatedTimeDetails: { heures: number, minutes: number } = { heures: 0, minutes: 0 };
  isSpinnerActive = false;

  constructor(
    private carService: CarService,
    private toastr: ToastrService,
  ) { }

  ngOnInit(): void {

  }

  // Validation pour la distance
  isDistanceValid(): boolean {
    const distance = parseFloat(this.formData.distance || '');
    return !isNaN(distance) && distance > 0;
  }


  // Calculer le temps en fonction des valeurs
  calculateTime(): void {

    this.calculatedTime = false;

    this.errors = { model: '', distance: '' };

    // Vérification des champs
    if (!this.formData.model) {
      this.errors.model = "Le modèle est requis";
    }

    if (!this.formData.distance) {
      this.errors.distance = "La distance est requise";
    } else if (!this.isDistanceValid()) {
      this.errors.distance = "La distance doit être un nombre valide et supérieur à zéro";
    }

    // Si des erreurs existent, on ne soumet pas
    if (this.errors.model || this.errors.distance) {
      console.log('Erreurs :', this.errors);
      return;
    }

    // Appel au service pour calculer le temps
    this.isSpinnerActive = true;
    this.carService.calculateTime(this.formData).subscribe(
      (result) => {
        console.log('Temps calculé avec succès :', result);
        this.isSpinnerActive = false;
        this.calculatedTime = true;
        this.calculatedTimeDetails.heures = result.temps.heures;
        this.calculatedTimeDetails.minutes = result.temps.minutes;

        this.toastr.success("Temps estimé affiché", "Résultat", {
          timeOut: 3000,
        });

      },
      (error) => {
        this.isSpinnerActive = false;
        console.log('Erreur de calcul du temps :', error);

        this.toastr.error(error, "Erreur", {
          timeOut: 3000,
        });

      }
    );
  }

}