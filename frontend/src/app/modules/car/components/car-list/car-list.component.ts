import { Component, OnInit , ViewChild, AfterViewInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { MatTableModule } from '@angular/material/table';
import { MatIconModule } from '@angular/material/icon';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatSortModule } from '@angular/material/sort';
import { MatDialog } from '@angular/material/dialog';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { MatPaginator } from '@angular/material/paginator';
import { MatTableDataSource } from '@angular/material/table';
import { MatDialogModule  } from '@angular/material/dialog';
import { MatButtonModule } from '@angular/material/button';
import { RouterLink } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerModule } from 'ngx-spinner';
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
    this.isSpinnerActive = true;

    this.carService.getCars().subscribe(
      (data: Car[]) => { 
        this.cars = data; 
        this.totalCars = this.cars.length;
        this.dataSource.data = this.cars;
        console.log("Voitures chargées :", this.cars);
  
        if (this.paginator) { 
          this.dataSource.paginator = this.paginator;
        }

        this.isSpinnerActive = false;
      },
      error => {
        console.error('Erreur de chargement des voitures:', error);
        this.toastr.error(error, "Erreur", {
          timeOut: 3000, 
        });
        this.isSpinnerActive = false;
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
        this.isSpinnerActive = false;
        this.toastr.success('Suppression réussie', 'Succès', { timeOut: 3000 });
        this.loadCars();
      },
      (error: string) => {
        this.isSpinnerActive = false;
        console.error('Erreur lors de la suppression de la voiture:', error);
        this.toastr.error(error, 'Erreur', { timeOut: 3000 });
      }
    );
  }

}

