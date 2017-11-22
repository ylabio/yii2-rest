<?php

namespace tests\data\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $text
 * @property string $preview
 * @property integer $author_id
 *
 * @property Comment[] $comments
 * @property Author $author
 * @property Tag[] $tags
 */
class Post extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'author_id'], 'required'],
            [['text'], 'string'],
            [['author_id'], 'integer'],
            [['preview'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'preview' => 'Preview',
            'author_id' => 'Author ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('rel_post_tag', ['post_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return ['author', 'tags', 'comments', 'comments.author'];
    }
}
