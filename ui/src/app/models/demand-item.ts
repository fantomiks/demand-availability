import {Equipment} from "./equipment";

export class DemandItem {
  public id!: bigint
  public qty!: bigint
  public available!: bigint
  public equipment!: Equipment
}
