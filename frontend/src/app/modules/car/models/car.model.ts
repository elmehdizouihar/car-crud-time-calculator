export interface Car {
    id?: number;
    model?: string;
    kmh? : number|null,
    caracteristiques?: { key: string, value: string }[];
  }
  