import {AfterViewChecked, Component, ElementRef, Renderer2, ViewChild} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import {CalendarOptions, FullCalendarComponent} from '@fullcalendar/angular';
import {EventResource} from "./models/event-resource";
import {Station} from "./models/station";
import {Stations} from "./models/stations";
import {Demand} from "./models/demand";
import {DemandItem} from "./models/demand-item";


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
})
export class AppComponent implements AfterViewChecked {

  // references the #calendar in the template
  @ViewChild('calendar') calendarComponent: FullCalendarComponent | any;

  title: string = "Demand calendar app"

  prev() {
    let calendarApi = this.calendarComponent.getApi();
    calendarApi.prev();
    this.events()
  }
  next() {
    let calendarApi = this.calendarComponent.getApi();
    calendarApi.next();
    this.events()


  }
  today() {
    let calendarApi = this.calendarComponent.getApi();
    calendarApi.today();
    this.events()
  }

  events() {
    let calendarApi = this.calendarComponent.getApi();

    let date = calendarApi.currentData.currentDate
    let start = new Date(date.getFullYear(), date.getMonth(), 1);

    this.month = this.monthName(date.getMonth() + 1)
    this.year = calendarApi.currentData.currentDate.getFullYear()

    this.detailsOpened = false

    this.httpClient
      .get('http://127.0.0.1:8080/demands/'+ this.currStation +'?start='+this.formatDate(start))
      .subscribe((res: any) => {
        this.Events.clear()
        for(let i = 0; i < res.data.length; i++){
          this.Events.set(res.data[i].id, res.data[i]);
        }
        this.calendarOptions.events = Array.from(this.Events.values())
      });

  }

  formatDate(date: Date) {
    let d = new Date(date);
    let month = (d.getMonth() + 1).toString().padStart(2, '0');
    let day = d.getDate().toString().padStart(2, '0');
    let year = d.getFullYear();
    return [year, month, day].join('-');
  }

  monthName(month: number):string{
    let returnMonth: string;

    switch(month) {
      case 1: returnMonth = "January"; break;
      case 2: returnMonth = "February"; break;
      case 3: returnMonth = "March"; break;
      case 4: returnMonth = "April"; break;
      case 5: returnMonth = "May"; break;
      case 6: returnMonth = "June"; break;
      case 7: returnMonth = "July"; break;
      case 8: returnMonth = "August"; break;
      case 9: returnMonth = "September"; break;
      case 10: returnMonth = "October"; break;
      case 11: returnMonth = "November"; break;
      case 12: returnMonth = "December"; break;
      default: returnMonth = "December";
    }

    return returnMonth;
  }

  month: string = "";
  year: string = "";


  stations: Station[] = []
  currStation: bigint | undefined
  detailsOpened: boolean = false

  Events = new Map<string, EventResource>();

  calendarOptions: CalendarOptions = {
    headerToolbar: false,
    themeSystem: 'bootstrap5',
    initialView: 'dayGridMonth',
    weekends: true,
    editable: false,
    selectable: true,
    selectMirror: false,
    dayMaxEvents: true,
    defaultAllDay: true
  };

  constructor(private httpClient: HttpClient, private elem: ElementRef, private renderer: Renderer2) {}
  onEventClick(res: any) {
    let event = this.Events.get(res.event.id.toString())
    if (event === undefined) {
      return
    }

    this.demandDetails = event.demand

    let trIndex = 0;
    for (let i=0; i<res.jsEvent.path.length; i++) {
      if (res.jsEvent.path[i].tagName == "TR") {
        trIndex = i
        break
      }
    }

    let parent = res.jsEvent.path[trIndex+1]
    let row = res.jsEvent.path[trIndex]

    row = this.renderer.nextSibling(row)
    this.renderer.insertBefore(parent, this.details, row)
    this.detailsOpened = true
  }

  details: any
  detailedClass: string = "bg-light"
  demandDetails: Demand = new Demand()

  ngOnInit() {
    setTimeout(() => {
      this.httpClient
        .get<Stations>('http://127.0.0.1:8080/stations')
        .subscribe((data: Stations) => {
          this.stations.push(...data.data)
          this.currStation = this.stations[0].id
          this.events();
        });
      }, 2000);

    setTimeout(() => {
      this.calendarOptions = {
        initialView: 'dayGridMonth',
        headerToolbar: false,
        eventClick: this.onEventClick.bind(this),
        events: Array.from(this.Events.values()),
      };

      }, 2500);
  }

  ngAfterViewChecked() {
    this.details = this.elem.nativeElement.querySelector('.detailed-row');
  }

  selectStation() {
    this.events()
  }

  isAvailable(): boolean {
    for (let i = 0; i < this.demandDetails.demandItems.length; i++) {
      if (!this.isEquipmentAvailable(this.demandDetails.demandItems[i])) {
        return false;
      }
    }
    return this.isCampervanAvailable()
  }
  isCampervanAvailable(): boolean {
    return this.demandDetails.available >= this.demandDetails.qty
  }
  isEquipmentAvailable(demandItem: DemandItem): boolean {
    return demandItem.available >= demandItem.qty
  }
}
