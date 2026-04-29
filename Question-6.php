<?php

/**
 * Refactor the following old legacy classes.
 *
 * Don't go too deep, estimate up to 15 minutes of work.
 *
 * The code shouldn't be ideal, rather adequate for the first step of the refactoring. Feel free to leave comments in places which can be improved in the future
 * if you see a possibility of that.
 */
final readonly class Document
{
    public function __construct(
        private string $name,
        private User $user,
    ) {
        if (mb_strlen($name) <= 5) {
            throw new InvalidArgumentException('Document name must be longer than 5 characters.');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

final readonly class DocumentRepository
{
    public function __construct(private Database $database) {}

    public function getTitle(Document $document): string
    {
        $row = $this->database->query(
            'SELECT title FROM document WHERE name = :name LIMIT 1',
            ['name' => $document->getName()],
        );

        if (!$row || !isset($row['title'])) {
            throw new RuntimeException(sprintf('Document "%s" not found.', $document->getName()));
        }

        return (string)$row['title'];
    }

    /**
     * Finds all documents for a given user.
     *
     * @param User $user
     * @return Document[]
     */
    public function findByUser(User $user): array
    {
        $rows = $this->database->query(
            'SELECT name FROM document WHERE user_id = :user_id',
            ['user_id' => $user->getId()]
        );

        $documents = [];
        foreach ($rows as $row) {
            $documents[] = new Document($row['name'], $user);
        }

        return $documents;
    }

    public function getAllDocuments(): array
    {
        $rows = $this->database->query('SELECT name FROM document');
        $documents = [];
        foreach ($rows as $row) {
            // Assuming User is retrievable; adapt as needed for actual User context.
            $documents[] = new Document($row['name'], new User());
        }

        return $documents;
    }
}

final class User
{
    public function makeNewDocument(string $name): Document
    {
        // Bug fix: original `if (!strpos(...))` treated a match at position 0 as "not found".
        if (stripos($name, 'senior') === false) {
            throw new InvalidArgumentException('The name should contain "senior".');
        }

        return new Document($name, $this);
    }

    public function getMyDocuments(DocumentRepository $documentRepository): array
    {
        return $documentRepository->findByUser($this);
    }

    public function getId(): int
    {
        // Stub for User's ID; implement based on your actual User entity.
        return 1;
    }
}