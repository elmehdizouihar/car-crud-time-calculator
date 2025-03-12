import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TimeCalculationComponent } from './time-calculation.component';

describe('TimeCalculationComponent', () => {
  let component: TimeCalculationComponent;
  let fixture: ComponentFixture<TimeCalculationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TimeCalculationComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TimeCalculationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
