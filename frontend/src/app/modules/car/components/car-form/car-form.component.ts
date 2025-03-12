import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatCardModule } from '@angular/material/card';
import { ToastrService } from 'ngx-toastr';
import { CarService } from '../../services/car.service';
import { Car } from '../../models/car.model';
import { LoadingSpinnerComponent } from '../../../../core/shared/components/loading-spinner/loading-spinner.component';

@Component({
  selector: 'app-car-form',
  imports: [
    FormsModule,
    CommonModule,
    MatInputModule,
    MatButtonModule,
    MatFormFieldModule,
    MatIconModule,
    MatCardModule,
    LoadingSpinnerComponent
  ],

  templateUrl: './car-form.component.html',
  styleUrls: ['./car-form.component.css']
})
export class CarFormComponent implements OnInit {
  car: Car = { model: '', kmh: null, caracteristiques: [] };
  editing: boolean = false;
  carId: number | null = null;
  voitures: any[] = [];
  // Définir l'objet 'errors'
  errors: { model: string, kmh: string, caracteristiques: string } = {
    model: '',
    kmh: '',
    caracteristiques: ''
  };
  isSpinnerActive = false;

  constructor(
    private carService: CarService,
    private route: ActivatedRoute,
    private router: Router,
    private httpClient: HttpClient,
    private toastr: ToastrService,

  ) { }

  ngOnInit(): void {
    const carId = this.route.snapshot.paramMap.get('id');
    if (carId) {
      this.carId = +carId;
      this.editing = true;
      this.carService.getCars().subscribe(
        (data) => {
          console.log("Data:", data);
          this.voitures = data.cars;
          console.log(this.voitures);
      
          const carToEdit = this.voitures.find((car) => car.id === this.carId);
          if (carToEdit) {
            this.car = { ...carToEdit };
          }
        },
        (error) => {
          console.error('Error:', error);
          this.toastr.error(error, "Erreur", {
            timeOut: 2000,
          });
        }
      );
      
    }
  }

  isKmValid(): boolean {
    // Vérifie si car.kmh est un nombre valide et supérieur à zéro
    return typeof this.car.kmh === 'number' && !isNaN(this.car.kmh) && this.car.kmh > 0;
  }

  saveCar(): void {
    console.log(this.car);

    // Vérification des erreurs
    this.errors = { model: '', kmh: '', caracteristiques: '' };

    if (!this.car.model) {
      this.errors.model = "Le modèle est requis";
    }

    if (!this.car.kmh) {
      this.errors.kmh = "Le Vitesse (en Km/h) est requis";
    } else if (this.car.kmh <= 0 || isNaN(this.car.kmh)) {
      this.errors.kmh = "Le Vitesse (en Km/h) doit être un nombre valide et supérieur à zéro";
    }

    if (this.car.caracteristiques) {
      this.car.caracteristiques = this.car.caracteristiques.filter(char => char.key || char.value);
    }

    // Validation des caractéristiques
    if (this.car.caracteristiques) {
      for (const char of this.car.caracteristiques) {
        if ((char.key && !char.value) || (!char.key && char.value)) {
          this.errors.caracteristiques = "Les caractéristiques doivent avoir une clé et une valeur si l'une est remplie.";
          break;
        }
      }
    }

    if (this.errors.model || this.errors.kmh || this.errors.caracteristiques) {
      console.log('Erreurs front:', this.errors);
      return;
    }

    // Si des erreurs existent, afficher et ne pas soumettre le formulaire
    if (this.errors.model || this.errors.kmh || this.errors.caracteristiques) {
      console.log('Erreurs front:', this.errors);
      return;
    }

    // Envoi de la requête si pas d'erreurs
    if (this.editing) {
      this.isSpinnerActive = true;

      this.carService.updateCar(this.carId!, this.car).subscribe(
        response => {
          console.log('Voiture mise à jour avec succès', response);
          this.toastr.success("Voiture mise à jour avec succès", "Succès", {
            timeOut: 3000,
          });
          this.isSpinnerActive = false;
          this.router.navigate(['/']);
        },
        error => {
          console.error(error);
          this.toastr.error(error, "Erreur", {
            timeOut: 1000,
          });
          this.isSpinnerActive = false;
        }
      );

    } else {
      this.isSpinnerActive = true;
      
      this.carService.addCar(this.car).subscribe(
        response => {
          console.log('Voiture ajoutée avec succès!');
          this.isSpinnerActive = false;
          this.toastr.success("Voiture ajoutée avec succès", "Succès", {
            timeOut: 3000,
          });
          this.router.navigate(['/']);
        },
        error => {
          console.log(error);
          this.toastr.error(error, "Erreur", {
            timeOut: 2000,
          });
          this.isSpinnerActive = false;

        }
      );
    }
  }

  addCharacteristic() {
    if (!this.car.caracteristiques) {
      this.car.caracteristiques = [];
    }
    this.car.caracteristiques.push({ key: '', value: '' });
  }

  removeCharacteristic(index: number) {
    if (this.car.caracteristiques) {
      this.car.caracteristiques.splice(index, 1);
    }
  }
}
