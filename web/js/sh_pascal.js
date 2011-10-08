if (! this.sh_languages) {
  this.sh_languages = {};
}
sh_languages['pascal'] = [
  [
    {
      'regex': /\b(?:[Aa][Ll][Ff][Aa]|[Aa][Nn][Dd]|[Aa][Rr][Rr][Aa][Yy]|[Bb][Ee][Gg][Ii][Nn]|[Cc][Aa][Ss][Ee]|[Cc][Oo][Nn][Ss][Tt]|[Dd][Ii][Vv]|[Dd][Oo]|[Dd][Oo][Ww][Nn][Tt][Oo]|[Ee][Ll][Ss][Ee]|[Ee][Nn][Dd]|[Ff][Aa][Ll][Ss][Ee]|[Ff][Ii][Ll][Ee]|[Ff][Oo][Rr]|[Ff][Uu][Nn][Cc][Tt][Ii][Oo][Nn]|[Gg][Ee][Tt]|[Gg][Oo][Tt][Oo]|[Ii][Ff]|[Ii][Nn]|[Ll][Aa][Bb][Ee][Ll]|[Mm][Oo][Dd]|[Nn][Ee][Ww]|[Nn][Oo][Tt]|[Oo][Ff]|[Oo][Rr]|[Pp][Aa][Cc][Kk]|[Pp][Aa][Cc][Kk][Ee][Dd]|[Pp][Aa][Gg][Ee]|[Pp][Rr][Oo][Gg][Rr][Aa][Mm]|[Pp][Uu][Tt]|[Pp][Rr][Oo][Cc][Ee][Dd][Uu][Rr][Ee]|[Rr][Ee][Aa][Dd]|[Rr][Ee][Aa][Dd][Ll][Nn]|[Rr][Ee][Cc][Oo][Rr][Dd]|[Rr][Ee][Pp][Ee][Aa][Tt]|[Rr][Ee][Ss][Ee][Tt]|[Rr][Ee][Ww][Rr][Ii][Tt][Ee]|[Ss][Ee][Tt]|[Tt][Ee][Xx][Tt]|[Tt][Hh][Ee][Nn]|[Tt][Oo]|[Tt][Rr][Uu][Ee]|[Tt][Yy][Pp][Ee]|[Uu][Nn][Pp][Aa][Cc][Kk]|[Uu][Nn][Tt][Ii][Ll]|[Vv][Aa][Rr]|[Ww][Hh][Ii][Ll][Ee]|[Ww][Ii][Tt][Hh]|[Ww][Rr][Ii][Tt][Ee][Ll][Nn]|[Ww][Rr][Ii][Tt][Ee])\b/g,
      'style': 'sh_keyword'
    },
    {
      'next': 1,
      'regex': /\(\*/g,
      'style': 'sh_comment'
    },
    {
      'next': 2,
      'regex': /\{/g,
      'style': 'sh_comment'
    },
    {
      'regex': /\b[+-]?(?:(?:0x[A-Fa-f0-9]+)|(?:(?:[\d]*\.)?[\d]+(?:[eE][+-]?[\d]+)?))u?(?:(?:int(?:8|16|32|64))|L)?\b/g,
      'style': 'sh_number'
    },
    {
      'next': 3,
      'regex': /"/g,
      'style': 'sh_string'
    },
    {
      'next': 4,
      'regex': /'/g,
      'style': 'sh_string'
    },
    {
      'regex': /\b(?:[Bb][Oo][Oo][Ll][Ee][Aa][Nn]|[Bb][Yy][Tt][Ee]|[Cc][Hh][Aa][Rr]|[Ii][Nn][Tt][Ee][Gg][Ee][Rr]|[Mm][Aa][Xx][Ii][Nn][Tt]|[Rr][Ee][Aa][Ll])\b/g,
      'style': 'sh_type'
    },
    {
      'regex': /~|!|%|\^|\*|\(|\)|-|\+|=|\[|\]|\\|:|;|,|\.|\/|\?|&|<|>|\|/g,
      'style': 'sh_symbol'
    },
    {
      'regex': /(?:[A-Za-z]|_)[A-Za-z0-9_]*[ \t]*(?=\()/g,
      'style': 'sh_function'
    }
  ],
  [
    {
      'exit': true,
      'regex': /\*\)/g,
      'style': 'sh_comment'
    },
    {
      'next': 1,
      'regex': /\(\*/g,
      'style': 'sh_comment'
    }
  ],
  [
    {
      'exit': true,
      'regex': /\}/g,
      'style': 'sh_comment'
    },
    {
      'next': 2,
      'regex': /\{/g,
      'style': 'sh_comment'
    }
  ],
  [
    {
      'exit': true,
      'regex': /$/g
    },
    {
      'regex': /\\(?:\\|")/g
    },
    {
      'exit': true,
      'regex': /"/g,
      'style': 'sh_string'
    }
  ],
  [
    {
      'exit': true,
      'regex': /$/g
    },
    {
      'regex': /\\(?:\\|')/g
    },
    {
      'exit': true,
      'regex': /'/g,
      'style': 'sh_string'
    }
  ]
];
