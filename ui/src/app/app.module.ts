import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppComponent } from './app.component';
// import modules
import { HttpClientModule } from '@angular/common/http';
import { FullCalendarModule } from '@fullcalendar/angular';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import {FormsModule} from "@angular/forms";
FullCalendarModule.registerPlugins([interactionPlugin, dayGridPlugin]);
@NgModule({
  declarations: [AppComponent],
    imports: [
        BrowserModule,
        FullCalendarModule,
        HttpClientModule,
        FormsModule,
    ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}
