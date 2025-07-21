<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use App\Models\User;
use App\Models\Message;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configure the database connection
$db = new DB;

$db->addConnection([
    'driver'    => 'mysql',
    'host'      => env('DB_HOST', '127.0.0.1'),
    'database'  => env('DB_DATABASE', 'forge'),
    'username'  => env('DB_USERNAME', 'forge'),
    'password'  => env('DB_PASSWORD', ''),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods...
$db->setAsGlobal();

// Setup the Eloquent ORM...
$db->bootEloquent();

try {
    // Test the connection
    $pdo = $db->getConnection()->getPdo();
    echo "✅ Successfully connected to the database.\n";
    
    // Check if messages table exists and has correct columns
    $tableExists = $pdo->query("SHOW TABLES LIKE 'messages'")->rowCount() > 0;
    
    if ($tableExists) {
        echo "✅ Messages table exists.\n";
        
        // Get table structure
        $columns = $pdo->query('DESCRIBE messages')->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'Field');
        
        echo "📋 Columns in messages table: " . implode(', ', $columnNames) . "\n";
        
        // Check for required columns
        $requiredColumns = ['id', 'sender_id', 'receiver_id', 'message', 'is_read', 'created_at', 'updated_at'];
        $missingColumns = array_diff($requiredColumns, $columnNames);
        
        if (empty($missingColumns)) {
            echo "✅ All required columns exist in messages table.\n";
        } else {
            echo "❌ Missing columns: " . implode(', ', $missingColumns) . "\n";
        }
        
        // Count existing messages
        $count = $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
        echo "📊 Number of messages in table: " . $count . "\n";
        
    } else {
        echo "❌ Messages table does not exist.\n";
    }
    
    // Check if users table exists and has role column
    $usersTableExists = $pdo->query("SHOW TABLES LIKE 'users'")->rowCount() > 0;
    
    if ($usersTableExists) {
        echo "✅ Users table exists.\n";
        
        // Check if role column exists
        $roleColumnExists = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'")->rowCount() > 0;
        
        if ($roleColumnExists) {
            echo "✅ Role column exists in users table.\n";
            
            // Count users by role
            $users = $pdo->query('SELECT role, COUNT(*) as count FROM users GROUP BY role')->fetchAll(PDO::FETCH_ASSOC);
            echo "👥 Users by role:\n";
            foreach ($users as $user) {
                echo "   - {$user['role']}: {$user['count']}\n";
            }
        } else {
            echo "❌ Role column does not exist in users table.\n";
        }
    } else {
        echo "❌ Users table does not exist.\n";
    }
    
    // Test creating a message (if we have users)
    $users = User::all();
    if ($users->count() >= 2) {
        echo "\n🧪 Testing message creation...\n";
        
        $sender = $users->first();
        $receiver = $users->where('id', '!=', $sender->id)->first();
        
        if ($sender && $receiver) {
            $message = Message::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'message' => 'Test message from chat system',
                'is_read' => false,
            ]);
            
            echo "✅ Test message created successfully!\n";
            echo "   - Message ID: {$message->id}\n";
            echo "   - From: {$sender->name} ({$sender->role})\n";
            echo "   - To: {$receiver->name} ({$receiver->role})\n";
            echo "   - Message: {$message->message}\n";
            
            // Test fetching messages between users
            $messages = Message::where(function ($query) use ($sender, $receiver) {
                $query->where('sender_id', $sender->id)
                    ->where('receiver_id', $receiver->id);
            })->orWhere(function ($query) use ($sender, $receiver) {
                $query->where('sender_id', $receiver->id)
                    ->where('receiver_id', $sender->id);
            })->get();
            
            echo "✅ Found " . $messages->count() . " messages between users.\n";
            
        } else {
            echo "❌ Need at least 2 users to test message creation.\n";
        }
    } else {
        echo "❌ Need at least 2 users to test message creation.\n";
    }
    
} catch (\PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 