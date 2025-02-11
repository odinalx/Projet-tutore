export interface User {
  id: string
  email: string
  role: 'admin' | 'user'
}

export interface ApiResponse<T> {
  data: T
  status: number
  message?: string
}
