import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface RecurringExpense {
  id: number;
  userId: number;
  calendarId: number;
  label: string;
  amount: number;
  categoryId?: number;
  startDate: string;
  interval: string;
  endDate?: string;
  isActive: boolean;
}

@Injectable({ providedIn: 'root' })
export class RecurringExpenseService {
  constructor(private http: HttpClient) {}

  list(): Observable<RecurringExpense[]> {
    return this.http.get<RecurringExpense[]>('/api/recurring-expenses');
  }

  create(data: Partial<RecurringExpense>): Observable<RecurringExpense> {
    return this.http.post<RecurringExpense>('/api/recurring-expenses', data);
  }

  update(id: number, data: Partial<RecurringExpense>): Observable<RecurringExpense> {
    return this.http.put<RecurringExpense>(`/api/recurring-expenses/${id}`, data);
  }

  delete(id: number): Observable<any> {
    return this.http.delete(`/api/recurring-expenses/${id}`);
  }
}
