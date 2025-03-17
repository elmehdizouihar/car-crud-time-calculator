import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Car } from '../models/car.model';
import { catchError } from 'rxjs/operators';
import { throwError } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CarService {

  private apiUrl = 'http://localhost:8000/api/cars';

  constructor(private http: HttpClient) { }

  // Fonction pour créer les en-têtes lorsque nécessaire
  private createHeaders(): HttpHeaders {
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    });
  }

  getCars(): Observable<Car[]> {
    return this.http.get<Car[]>(this.apiUrl).pipe(
      catchError((error: HttpErrorResponse) => {
        let errorMessage = 'Une erreur est survenue lors de la récupération des voitures.';

        console.error('Erreur technique :', error);

        if (error.status === 404) {
          errorMessage = 'Aucune voiture trouvée.';
          console.log('Erreur 404 : La ressource demandée n\'existe pas.'); // Log spécifique pour les 404
        } else if (error.error && error.error.detail) {
          console.error('Erreur technique :', error);
          // Message générique pour l'utilisateur
          errorMessage = 'Une erreur est survenue. Veuillez réessayer plus tard.';
        } else {
          // Log des erreurs inconnues
          console.error('Erreur inconnue :', error.message || error);
        }

        return throwError(errorMessage);
      })
    );
  }

  addCar(carData: any): Observable<any> {
    const headers = this.createHeaders();
    return this.http.post<any>(this.apiUrl, carData, { headers }).pipe(
      catchError((error: HttpErrorResponse) => {
        let errorMessage = 'Une erreur inconnue est survenue.';

        if (error.status === 422 && error.error.violations) {
          // Extraire les messages d'erreur de validation et les formater en une seule chaîne avec <br>
          errorMessage = error.error.violations
            .map((violation: any) => violation.message)
            .join('<br>');
        } else if (error.status === 400 && error.error && error.error.detail) {
          // Capturer les erreurs générales avec un message détaillé
          errorMessage = error.error.detail;

        } else {
          console.error('Erreur technique :', error);
        }

        return throwError(errorMessage);
      })
    );
  }

  updateCar(id: number, carData: any): Observable<any> {
    const headers = this.createHeaders();
    return this.http.put<any>(`${this.apiUrl}/${id}`, carData, { headers }).pipe(
      catchError((error: HttpErrorResponse) => {
        let errorMessage = 'Une erreur inconnue est survenu e.';

        if (error.status === 422 && error.error.violations) {
          // Extraire les messages d'erreur de validation et les formater en une seule chaîne avec <br>
          errorMessage = error.error.violations
            .map((violation: any) => violation.message)
            .join('<br>');
        } else if (error.status === 400 && error.error && error.error.detail) {
          errorMessage = error.error.detail;
        }

        console.error('Erreur technique :', error);

        return throwError(errorMessage);
      })
    );
  }

  deleteCar(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`).pipe(
      catchError((error: HttpErrorResponse) => {
        let errorMessage = 'Une erreur est survenue.'; // Message générique pour l'utilisateur

        if (error.status === 404) {
          // Cas spécifique : la voiture n'existe pas
          errorMessage = 'La voiture que vous essayez de supprimer n\'existe pas.';
        } else {
          console.error('Erreur serveur :', error);
        }
        return throwError(errorMessage);
      })
    );
  }
  calculateTime(data: any): Observable<any> {
    const headers = this.createHeaders();
    return this.http.post<any>(`${this.apiUrl}/calculate_travel_time`, data, { headers }).pipe(
      catchError((error: HttpErrorResponse) => {
        let errorMessage = 'Une erreur inconnue est survenue.';

        if (error.status === 400 || error.status === 404) {
          // Capturer les messages d'erreur spécifiques pour les erreurs 400 et 404
          errorMessage = error.error.detail;
        }

        console.error('Erreur technique :', error);

        return throwError(errorMessage);
      })
    );
  }
}
