import { HubConnection, HubConnectionBuilder, LogLevel } from '@microsoft/signalr'

class SignalRService {
  private connection: HubConnection | null = null

  async startConnection(url: string): Promise<void> {
    try {
      this.connection = new HubConnectionBuilder()
        .withUrl(url)
        .withAutomaticReconnect()
        .configureLogging(LogLevel.Information)
        .build()

      await this.connection.start()
      console.log('SignalR Connected')
    } catch (error) {
      console.error('SignalR connection error:', error)
      throw error
    }
  }

  async stopConnection(): Promise<void> {
    if (this.connection) {
      await this.connection.stop()
      this.connection = null
      console.log('SignalR Disconnected')
    }
  }

  on(methodName: string, callback: (...args: any[]) => void): void {
    if (this.connection) {
      this.connection.on(methodName, callback)
    }
  }

  off(methodName: string, callback?: (...args: any[]) => void): void {
    if (this.connection) {
      if (callback) {
        this.connection.off(methodName, callback)
      } else {
        this.connection.off(methodName)
      }
    }
  }

  async invoke(methodName: string, ...args: any[]): Promise<any> {
    if (this.connection) {
      return await this.connection.invoke(methodName, ...args)
    }
    throw new Error('SignalR connection not established')
  }

  get isConnected(): boolean {
    return this.connection?.state === 'Connected'
  }

  get connectionState(): string | undefined {
    return this.connection?.state
  }
}

export const signalRService = new SignalRService()
