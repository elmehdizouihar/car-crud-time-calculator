import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Car } from '../models/car.model';
import { catchError } from 'rxjs/operators';
import { throwError } from 'rxjs';

interface CarResponse {
  cars: Car[];
}

@Injectable({
  providedIn: 'root'
})
export class CarService {

  private apiUrl = 'http://localhost:8000/api/cars';

  constructor(private http: HttpClient) {}

  // Fonction pour créer les en-têtes lorsque nécessaire
  private createHeaders(): HttpHeaders {
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    });
  }

  getCars(): Observable<CarResponse> {
    return this.http.get<CarResponse>(this.apiUrl).pipe(
      catchError((error) => {
        if (error.error && error.error.message) {
          return throwError(error.error.message);
        } else {
          return throwError('Une erreur inconnue est survenue lors de la récupération des voitures.');
        }
      })
    );
  }

  addCar(carData: any): Observable<any> {
    const headers = this.createHeaders(); 
    return this.http.post<any>(this.apiUrl, carData, { headers }).pipe(
      catchError((error) => {
        if (error.error && error.error.message) {
          return throwError(error.error.message);
        } else {
          return throwError('Une erreur inconnue est survenue.');
        }
      })
    );
  }

  updateCar(id: number, car: Car): Observable<Car> {
    const headers = this.createHeaders(); 
    return this.http.put<Car>(`${this.apiUrl}/${id}`, car, { headers }).pipe(
      catchError((error) => {
        if (error.error && error.error.message) {
          return throwError(error.error.message);
        } else {
          return throwError('Une erreur inconnue est survenue.');
        }
      })
    );
  }

  deleteCar(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`).pipe(
      catchError((error) => {
        if (error.error && error.error.message) {
          return throwError(error.error.message);
        } else {
          return throwError('Une erreur inconnue est survenue lors de la suppression de la voiture.');
        }
      })
    );
  }

  calculateTime(data: any): Observable<any> {
    const headers = this.createHeaders(); 
    return this.http.post<any>(`${this.apiUrl}/calculate-time`, data, { headers }).pipe(
      catchError((error) => {
        if (error.error && error.error.message) {
          return throwError(error.error.message);
        } else {
          return throwError('Une erreur inconnue est survenue.');
        }
      })
    );
  }
}
