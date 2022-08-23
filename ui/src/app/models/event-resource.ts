import {Demand} from "./demand";

export class EventResource {
  public id: string = ""
  public title: string = ""
  public start: string = ""
  // public end: string = ""
  public demand!: Demand
}
