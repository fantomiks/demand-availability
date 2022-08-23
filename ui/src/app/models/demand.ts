import {Customer} from "./customer";
import {Station} from "./station";
import {Campervan} from "./campervan";
import {DemandItem} from "./demand-item";

export class Demand {
  public id!: number
  public qty!: number
  public available!: number
  public customer!: Customer
  public startStation!: Station
  public endStation!: Station
  public campervan!: Campervan
  public rentalStartDate!: Date
  public rentalEndDate!: Date
  public demandItems!: DemandItem[]
}
