<?php

namespace tests\data;

/**
 * @inheritdoc
 */
class Migration extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('author', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'age' => $this->integer(),
            'status' => $this->boolean(),
        ]);

        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'text' => $this->text()->notNull(),
            'preview' => $this->string(255),
            'author_id' => $this->integer()->notNull(),
            'FOREIGN KEY(author_id) REFERENCES author(id)',
        ]);

        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'text' => $this->text()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
            'FOREIGN KEY(author_id) REFERENCES author(id)',
            'FOREIGN KEY(post_id) REFERENCES post(id)',
        ]);

        $this->createTable('tag', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);

        $this->createTable('rel_post_tag', [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
            'PRIMARY KEY(post_id, tag_id)',
            'FOREIGN KEY(post_id) REFERENCES post(id)',
            'FOREIGN KEY(tag_id) REFERENCES tag(id)',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('rel_post_tag');
        $this->dropTable('tag');
        $this->dropTable('comment');
        $this->dropTable('post');
        $this->dropTable('author');
    }
}
