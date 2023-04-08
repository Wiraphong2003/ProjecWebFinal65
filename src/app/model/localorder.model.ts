export interface Localorder {
  fid: string;
  name: string;
  amount: Number;
  img: string;
  sunamount: Number;
}

// Converts JSON strings to/from your types
export class Convert {
  public static toFood(json: string): Localorder {
    return JSON.parse(json);
  }

  public static foodToJson(value: Localorder): string {
    return JSON.stringify(value);
  }
}
