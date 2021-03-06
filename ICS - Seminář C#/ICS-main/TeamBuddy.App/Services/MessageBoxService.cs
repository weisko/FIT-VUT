using System.Windows;

namespace TeamBuddy.App.Services
{
    public class MessageBoxService : IMessageBoxService
    {
        public MessageBoxResult Show(string messageBoxText, string caption, MessageBoxButton button)
        {
            return MessageBox.Show(messageBoxText, caption, button);
        }
    }
}
