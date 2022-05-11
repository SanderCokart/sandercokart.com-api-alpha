<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title
 * @property string $excerpt
 * @property string $markdown
 * @property string $slug
 * @property string|null $published_at
 * @property int $user_id
 * @property int $article_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ArticleType|null $articleType
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $banner
 * @property-read int|null $banner_count
 * @method static \Illuminate\Database\Eloquent\Builder|Article drafts()
 * @method static \Database\Factories\ArticleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article published()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMarkdown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUserId($value)
 */
	class Article extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ArticleType
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @method static \Database\Factories\ArticleTypeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType whereName($value)
 */
	class ArticleType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Authenticatable
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Authenticatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authenticatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authenticatable query()
 */
	class Authenticatable extends \Eloquent implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\Access\Authorizable, \App\Contracts\CanResetPasswordContract, \App\Contracts\CanChangePasswordContract, \App\Contracts\CanChangeEmailContract, \App\Contracts\CanResetEmailContract, \App\Contracts\MustVerifyEmailContract {}
}

namespace App\Models{
/**
 * App\Models\CompromisedEmail
 *
 * @property string $identifier
 * @property string $token
 * @property int $user_id
 * @property string $expires_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedEmail query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedEmail whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedEmail whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedEmail whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedEmail whereUserId($value)
 */
	class CompromisedEmail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CompromisedPassword
 *
 * @property string $identifier
 * @property string $token
 * @property int $user_id
 * @property string $expires_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedPassword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedPassword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedPassword query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedPassword whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedPassword whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedPassword whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompromisedPassword whereUserId($value)
 */
	class CompromisedPassword extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmailVerification
 *
 * @property string $identifier
 * @property string $token
 * @property string $expires_at
 * @method static \Database\Factories\EmailVerificationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerification whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerification whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailVerification whereToken($value)
 */
	class EmailVerification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\File
 *
 * @property int $id
 * @property string $relative_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @method static \Database\Factories\FileFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereRelativePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 */
	class File extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PasswordReset
 *
 * @property string $identifier
 * @property string $token
 * @property int $user_id
 * @property string $expires_at
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereUserId($value)
 */
	class PasswordReset extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RoleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RoleUser
 *
 * @property int $role_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUserId($value)
 */
	class RoleUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $deleted_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

