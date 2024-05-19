<?php
namespace App\Enums;

class ChatActivityEnum
{
    const CREATED = "CREATED";
    const LOCKED = "LOCKED";
    const UNLOCKED = "UNLOCKED";
    const DESTROYED = "DESTROYED";

    public static function getChatActivity()
    {
        return [self::CREATED, self::LOCKED, self::UNLOCKED, self::DESTROYED];
    }

    public static function getChatCreatedMessage(string $name)
    {
        return "A new chat has been successfully created between you and <strong>" .
            htmlspecialchars($name, ENT_QUOTES, "UTF-8") .
            "</strong>.<br/><br/> All your conversation is completely end-to-end encrypted using a zero-knowledge proof sodium hashing algorithm. A chat secret will be required before starting the chat, which will be used to encrypt your messages.<br/><br/> <strong style='color:#d33'>Important:</strong> If the chat secret is entered in reverse when opening the chat subsequently, the chat will be locked and can only be unlocked by the other person. If the other person also locks their chat by entering the reverse secret while the chat is locked from this end, the chat will undergo a self-destruct process, effectively deleting the chat and all associated messages.";
    }

    public static function getChatLockedMessageSelf(string $name)
    {
        return "Your chat with <strong>" .
            htmlspecialchars($name, ENT_QUOTES, "UTF-8") .
            "</strong> has been locked and cannot be accessed. To unlock the chat, kindly contact your partner.<br/><br/><strong style='color:#d33'>Important:</strong> If your partner locks this chat while yours is stilled locked, this chat will undergo a self-destruct process, effectively deleting the chat and all associated messages.";
    }

    public static function getChatLockedMessagePartner(string $name)
    {
        return "<strong>" .
            htmlspecialchars($name, ENT_QUOTES, "UTF-8") .
            "</strong> has just locked their chat. To unlock their chat, kindly naviagte to the chat settings and click the unlock icon.<br/><br/><strong style='color:#d33'>Important:</strong> If you locked this chat while theirs is stilled locked, this chat will undergo a self-destruct process, effectively deleting the chat and all associated messages.";
    }

    public static function getChatUnlockMessageSelf(string $name)
    {
        return "You have just unlocked <strong>" .
            htmlspecialchars($name, ENT_QUOTES, "UTF-8") .
            "</strong> chat. They can now access the chat";
    }

    public static function getChatUnlockMessagePartner(string $name)
    {
        return "Your chat with <strong>" .
            htmlspecialchars($name, ENT_QUOTES, "UTF-8") .
            "</strong> has been unlocked. You can now access the chat";
    }

    public static function getChatSelfDestructMessage(string $name)
    {
        return "Your chat with <strong>" .
            htmlspecialchars($name, ENT_QUOTES, "UTF-8") .
            "</strong> has just undergone a self-destruct process thereby effectively deleting the chat and all associated messages. To chat with your partner, you will have to send another chat request.";
    }
}
