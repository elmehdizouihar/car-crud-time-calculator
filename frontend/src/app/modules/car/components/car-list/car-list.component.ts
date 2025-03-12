// src/app/car-list/car-list.component.ts
import { Component, OnInit , ViewChild, AfterViewInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common'; // Importer CommonModule
import { MatTableModule } from '@angular/material/table';
import { MatIconModule } from '@angular/material/icon';
import { MatPaginatorModule } from '@angular/material/paginator'; // Si vous voulez paginer
import { MatSortModule } from '@angular/material/sort'; // Pour trier la table
// import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatDialog } from '@angular/material/dialog';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
// import { BrowserModule } from '@angular/platform-browser';
import { MatPaginator } from '@angular/material/paginator';
import { MatTableDataSource } from '@angular/material/table'; // Ajout de MatTableDataSource
import { MatDialogRef } from '@angular/material/dialog';

import { MatSnackBar } from '@angular/material/snack-bar'; // Importez MatSnackBar
import { MatSnackBarConfig } from '@angular/material/snack-bar'; // Importez MatSnackBarConfig
import { MatDialogModule  } from '@angular/material/dialog';  // Ajouter MatDialogModule
import { MatButtonModule } from '@angular/material/button';  // Ajouter MatButtonModule
import { RouterOutlet, RouterLink } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService, NgxSpinnerModule } from 'ngx-spinner';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { ConfirmDialogComponent } from '../../../../core/shared/components/confirm-dialog/confirm-dialog.component';
import { LoadingSpinnerComponent } from '../../../../core/shared/components/loading-spinner/loading-spinner.component';
import { CarService } from '../../services/car.service';
import { Car } from '../../models/car.model';


@Component({
  selector: 'app-car-list',
  imports: [MatDialogModule,MatButtonModule,HttpClientModule,FormsModule, CommonModule,
    MatTableModule,
    MatIconModule,
    MatButtonModule,
    MatPaginatorModule,
    MatSortModule,
    // BrowserAnimationsModule,
    HttpClientModule,
    // BrowserModule,
    MatPaginator,
    RouterLink,
    NgxSpinnerModule,
    MatProgressSpinnerModule,
    LoadingSpinnerComponent
  ],
  

  templateUrl: './car-list.component.html',
  styleUrls: ['./car-list.component.css'],
  
})

export class CarListComponent implements OnInit, AfterViewInit {
  cars: any[] = [];
  displayedColumns: string[] = ['model', 'kmh', 'caracteristiques', 'actions'];
  dataSource = new MatTableDataSource<any>([]);
  totalCars = 0;
  pageSize = 5;
  carIdToDelete: number | null = null;
  isSpinnerActive = false; 

  @ViewChild(MatPaginator) paginator: MatPaginator | undefined;

  constructor(
    private carService: CarService,
    private matDialog: MatDialog,
    private httpClient: HttpClient,
    private toastr: ToastrService,
  ) {}

  ngOnInit(): void {
    this.loadCars();
  }

  loadCars(): void {
    this.carService.getCars().subscribe(
      data => {
        this.cars = data.cars;
        this.totalCars = this.cars.length;
        this.dataSource.data = this.cars;
        console.log(this.cars);
        
        // Mettre à jour le paginator après que les données soient chargées
        if (this.paginator) { 
          this.dataSource.paginator = this.paginator;
        }
      },
      error => {
        console.error('Erreur de chargement des voitures:', error);
        this.toastr.error(error, "Erreur", {
          timeOut: 3000, 
        });
      }
    );
    
  }

  ngAfterViewInit(): void {
    // Lier correctement le paginator à MatTableDataSource
    if (this.paginator) {
      this.dataSource.paginator = this.paginator;
    }
  }

  confirmDelete(id: number): void {
    const dialogRef = this.matDialog.open(ConfirmDialogComponent);

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.deleteCar(id);
      }
    });
  }

  deleteCar(id: number): void {
    this.isSpinnerActive = true;
    this.carService.deleteCar(id).subscribe(
      () => {
        console.log('Voiture supprimée');
        this.isSpinnerActive = false;
        this.toastr.success("Suppression réussie", "Succès", {
          timeOut: 3000, 
        });
        this.loadCars(); 
      },
      error => {
        this.isSpinnerActive = false;
        console.error('Erreur lors de la suppression de la voiture:', error);
        this.toastr.error(error, "Erreur", {
          timeOut: 3000, 
        });
      }
    );
  }
  

  editCar(id: number): void {
    console.log('Modifier la voiture avec l\'ID:', id);
  }


  onPageChange(event: any): void {
    console.log('Changement de page:', event);
  }
}

