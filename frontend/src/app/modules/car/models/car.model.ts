export interface Car {
    id?: number;
    model?: string;
    kmh? : number|null,
    caracteristiques?: { cle: string, value: string }[];
  }
  