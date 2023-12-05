<?php

declare(strict_types=1);

/**
 * Main Class For App
 */
class HangmanGame
{
    private static ?array $wordList;
    private static string $randomWord = '';
    private static string $securedWord = '';
    private static int $incorrectAttempts = 0;
    private const MAX_INCORRECT_ATTEMPTS = 6;
    private const SUMMARY_GAME_MESSAGE = 'Maximum number of attempts ';
    private const START_GAME_MESSAGE = 'Game Started,try to guess the word';
    private const CONGRATULATION_GAME_MESSAGE = 'Congratulations! You guessed the word: ';
    private const PREVIEW_MESSAGE = 'Press "S" to start the game or "E" to exit from the game: ';
    private const LOST_GAME_MESSAGE = 'You lost try again! ' . PHP_EOL . ' The word you failed to guess is: ';
    private const ATTENTION_GAME_MESSAGE = 'Attention: Input must be an option "S" or "E" !';


    public function __construct(?array $decodedJsonData = null)
    {
        require_once ROOT_DIR . '/src/Enums/GameChoices.php';
        require_once ROOT_DIR . '/src/Enums/GameProgress.php';
        require_once ROOT_DIR . '/src/Exceptions/InvalidInputException.php';

        self::$wordList = $decodedJsonData;
        self::initializeGame();
    }

    private static function initializeGame(): void
    {
        self::$randomWord  = self::getRandomWord(data: self::$wordList);
        self::$securedWord = self::encodeWordWithStar(word: self::$randomWord);

        self::menuGame();
    }

    private static function menuGame(): void
    {
        echo self::PREVIEW_MESSAGE;
        $userChoice = self::getConsoleInput();

        echo match ($userChoice) {
            GameChoices::START => self::startGame(),
            GameChoices::EXIT => self::finishGame(),
            default => self::showAttentionGameMessage(),
        };
    }

    private static function startGame(): void
    {
        echo "Starting the game..." . PHP_EOL;

        sleep(2); // Wait for 2 seconds before start

        echo self::START_GAME_MESSAGE . " with " . strlen(self::$randomWord) . " letters" . PHP_EOL;

        self::displayGameProgress();
    }

    private static function finishGame(): void
    {
        echo "Exiting the game..." . PHP_EOL;

        sleep(2); // Wait for 2 seconds before exit

        echo "Exited the game" . PHP_EOL;
    }

    /**
     * Reset game state for a new round
     */
    private static function refreshGame(): void
    {
        self::$randomWord        = self::getRandomWord(data: self::$wordList);
        self::$securedWord       = self::encodeWordWithStar(word: self::$randomWord);
        self::$incorrectAttempts = 0;

        self::menuGame();
    }

    private static function displayGameProgress(): void
    {
        print_r(self::$randomWord . PHP_EOL);
        echo self::$securedWord . PHP_EOL;
        echo GameProgress::PROGRESS_START . PHP_EOL;

        while (true) {
            $userInput = self::getConsoleInput();

            // Check if the guessed letter is in the secret word and update the secured word
            self::$securedWord = self::guessLetter(
                letter: $userInput,
                secretWord: self::$randomWord,
                securedWord: self::$securedWord
            );

            // Get the corresponding progress based on the number of incorrect attempts
            $progress = GameProgress::values()[self::$incorrectAttempts] ?? GameProgress::PROGRESS_END;

            echo self::$securedWord . PHP_EOL;
            echo self::SUMMARY_GAME_MESSAGE . self::MAX_INCORRECT_ATTEMPTS . ' left ' . self::MAX_INCORRECT_ATTEMPTS - self::$incorrectAttempts . ' more to go' . PHP_EOL;
            echo $progress . PHP_EOL;

            if (self::$incorrectAttempts === self::MAX_INCORRECT_ATTEMPTS) {
                echo self::LOST_GAME_MESSAGE . self::$randomWord . PHP_EOL;
                self::refreshGame();
                break;
            }

            // Check if the word has been completely guessed
            if (strtolower(self::$securedWord) === strtolower(self::$randomWord)) {
                echo self::CONGRATULATION_GAME_MESSAGE . self::$randomWord . PHP_EOL;
                self::refreshGame();
                break;
            }
        }
    }

    private static function guessLetter(string $letter, string $secretWord, string $securedWord): string
    {
        $letter = strtolower($letter);

        $positions = [];

        // Find all occurrences of the guessed letter in the secret word
        for ($i = 0; $i < strlen($secretWord); $i++) {
            if (strtolower($secretWord[$i]) === $letter) {
                $positions[] = $i;
            }
        }

        // If no occurrences found, increase incorrect attempts
        if (empty($positions)) {
            self::$incorrectAttempts++;
        }

        // Update the secured word with the guessed letter at the found positions
        foreach ($positions as $position) {
            $securedWord[$position] = $letter;
        }

        return $securedWord;
    }

    private static function getConsoleInput(): string|bool
    {
        $userInput = trim(fgets(STDIN));

        return self::validateConsoleInput(inputText: $userInput);
    }

    private static function validateConsoleInput(string $inputText): string
    {
        while (true) {
            try {
                // Convert the choice to uppercase for case-insensitivity
                $inputText = strtoupper($inputText);

                // Check if it's a single letter
                if (strlen($inputText) !== 1) {
                    throw new InvalidInputException("Input must be a single letter");
                }

                // Check if it's an English letter
                if ( ! preg_match('/^[A-Z]$/', $inputText)) {
                    throw new InvalidInputException("Input must be a single English letter");
                }

                return $inputText;
            } catch (InvalidInputException $error) {
                // Catch the exception and display the error message
                echo "Error: " . $error->getMessage() . PHP_EOL;

                echo "Please enter a valid value: ";
                $inputText = self::getConsoleInput();
            }
        }
    }

    /**
     * Randomly select a word from the list
     */
    private static function getRandomWord(array $data): string
    {
        return $data[array_rand($data)];
    }

    /**
     * Mask the word for security (replace each character with '*')
     */
    private static function encodeWordWithStar(string $word): string
    {
        return str_repeat('*', strlen($word));
    }

    private static function showAttentionGameMessage(): void
    {
        echo self::ATTENTION_GAME_MESSAGE . PHP_EOL;
        self::refreshGame();
    }
}
