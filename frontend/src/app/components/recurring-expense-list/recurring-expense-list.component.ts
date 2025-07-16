import { Component, OnInit } from '@angular/core';
import { RecurringExpense, RecurringExpenseService } from '../../services/recurring-expense.service';

@Component({
  selector: 'app-recurring-expense-list',
  templateUrl: './recurring-expense-list.component.html',
})
export class RecurringExpenseListComponent implements OnInit {
  recurringExpenses: RecurringExpense[] = [];

  constructor(private recurringService: RecurringExpenseService) {}

  ngOnInit() {
    this.recurringService.list().subscribe(data => this.recurringExpenses = data);
  }
}
